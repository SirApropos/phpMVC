<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:07 PM
 */

class MappingUtils {
	/**
	 * @param array $arr
	 * @param $clazz
	 * @return mixed
	 */
	public static function bindObject(array $arr, $clazz){
		$timer = Timer::create("Binding $clazz", "binding");
		if(!$clazz instanceof ReflectionClass){
			$clazz = new ReflectionClass($clazz);
		}
		$clazzName = $clazz->getName();
		$mapping = ReflectionUtils::getMapping($clazzName, "ModelMapping");
		/**
		 * @var ModelMapping $mapping
		 */
		$fields = $mapping->getMappings();
		$obj = IOCContainer::getInstance()->newInstance($clazz);
		foreach($arr as $key => $value) {
			$failOnUnknown = true;
			$val = null;
			if (isset($fields[$key])) {
				if ($fields[$key]->getIsArray()) {
					$val = [];
					foreach ($value as $vKey => $vValue) {
						$val[$vKey] = self::bindObject($vValue, $fields[$key]->getType());
					}
				} else {
					$val = self::bindObject($value, $fields[$key]->getType());
				}
			} else {
				$failOnUnknown = false;
				$val = $value;
			}
			if ($failOnUnknown || ReflectionUtils::hasProperty($obj, $key)) {
				ReflectionUtils::setProperty($obj, $key, $val);
			}
		}
		$timer->stop();
		return $obj;
	}

	public static function getObjectVars($obj, MappingConfiguration $config=null, array &$hasMapped = array()){
		if(is_null($config)){
			$config = new MappingConfiguration();
		}
		if(is_object($obj)){
			$clazz = new ReflectionClass(get_class($obj));
			if (in_array($obj, $hasMapped)) {
				$result = "[" . $clazz->getName() . " Recursion]";
			} else {
				$result = [];
				$hasMapped[] = $obj;
				foreach ($clazz->getProperties() as $property) {
					if (!$property->isStatic()) {
						$property->setAccessible(true);
						$value = self::getObjectVars($property->getValue($obj), $config, $hasMapped);
						self::_addToResult($config, $result, $property->getName(), $value);
					}
				}
			}
		}else if(is_array($obj)) {
			$result = [];
			foreach ($obj as $key => $value) {
				$value = self::getObjectVars($value, $config, $hasMapped);
				self::_addToResult($config, $result, $key, $value);
			}
		}else{
			$result = $obj;
		}
		return $result;
	}

	private static function _addToResult(MappingConfiguration $config, &$result, $key, $value){
		if(!is_null($value) || $config->getInclusion() == MappingConfiguration::INCLUSION_NULL ) {
			$result[$key] = $value;
		}
	}
}