<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 6:56 PM
 */
class ReflectionUtils
{

	/**
	 * @param $obj
	 * @param $name
	 * @return ReflectionProperty
	 * @throws ModelBindException
	 */
	public static function getProperty($obj, $name){
		$clazz = self::getReflectionClass($obj);
		if(!$clazz->hasProperty($name)){
			throw new ModelBindException("No such property '".$name."' exists in class '".$clazz->getName()."'.");
		}
		$field = $clazz->getProperty($name);
		$field->setAccessible(true);
		return $field;
	}

	public static function getPropertyValue($obj, $name){
		return self::getProperty($obj,$name)->getValue($obj);
	}

	/**
	 * @param $obj
	 * @param $mappingClazz
	 * @return object
	 * @throws IllegalArgumentException
	 */
	public static function getMapping($obj, $mappingClazz){
		if(!$mappingClazz instanceof ReflectionClass){
			$mappingClazz = new ReflectionClass($mappingClazz);
		}
		if($mappingClazz->implementsInterface("Mapping")){
			$result = $mappingClazz->newInstance();
			$clazz = self::getReflectionClass($obj);
			while($clazz instanceof ReflectionClass) {
				if($clazz->hasProperty("mapping")) {
					$result->bind($clazz, self::getPropertyValue($clazz, "mapping"));
				}
				$clazz = $clazz->getParentClass();
			}
			return $result;
		}else{
			throw new IllegalArgumentException("Mapping class must implement Mapping interface.");
		}
	}

	public static function setProperty($obj, $field, $value){
		$prop = self::getProperty($obj, $field);
		$prop->setValue($obj, $value);
	}

	/**
	 * @param $obj
	 * @return ReflectionClass
	 */
	public static function getReflectionClass($obj) {
        $result = $obj;
        if (!$result instanceof ReflectionClass) {
            $result = is_object($obj) ? get_class($obj) : $obj;
            $result = new ReflectionClass($result);
        }
   		return $result;
	}

	/**
	 * @param $obj
	 * @param $name
	 * @return bool
	 */
	public static function hasProperty($obj, $name){
		return self::getReflectionClass($obj)->hasProperty($name);
	}

	public static function getRecursiveClasses($clazz){
		$clazz = self::getReflectionClass($clazz);
		$classes = [];
		do{
			array_unshift($classes, $clazz);
		}while($clazz = $clazz->getParentClass());
		return $classes;
	}

	public static function getRecursiveProperties($obj, $name){
		$result = [];
		foreach(self::getRecursiveClasses($obj) as $clazz){
			if(self::hasProperty($clazz, $name)){
				$result[] = self::getPropertyValue($clazz, $name);
			}
		}
		return $result;
	}
}
