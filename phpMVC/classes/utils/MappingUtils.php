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
		foreach($arr as $key => $value){
			$val = null;
			if(isset($fields[$key])){
				if($fields[$key]->getIsArray()){
					$val = [];
					foreach($value as $vKey => $vValue){
						$val[$vKey] = self::bindObject($vValue, $fields[$key]->getType());
					}
				}else{
					$val = self::bindObject($value, $fields[$key]->getType());
				}
			}else{
				$val = $value;
			}
			ReflectionUtils::setProperty($obj, $key, $val);
		}
		$timer->stop();
		return $obj;
	}

	public static function getObjectVars($obj, array &$hasMapped = array()){
		if(is_object($obj)){
			$clazz = new ReflectionClass(get_class($obj));
			if(!in_array($obj, $hasMapped)){
				$hasMapped[] = $obj;
				$result = [];
				foreach($clazz->getProperties() as $property){
					$property->setAccessible(true);
					$value = self::getObjectVars($property->getValue($obj), $hasMapped);
					$result[$property->getName()] = $value;
				}
			}else{
				$result = "[".$clazz->getName()." Recursion]";
			}
		}else if(is_array($obj)){
			$result = [];
			foreach($obj as $key => $value){
				$result[$key] = self::getObjectVars($value, $hasMapped);
			}
		}else{
			$result = $obj;
		}
		return $result;
	}
}
?>