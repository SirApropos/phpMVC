<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 11/20/2014
 * Time: 6:03 PM
 */

abstract class ObjectMapper implements Mapper {

	/**
	 * @param string $contentType
	 * @return bool
	 */
	public function canRead($contentType)
	{
		return $this->isCompatibleWith($contentType);
	}

	/**
	 * @param string $contentType
	 * @return bool
	 */
	public function canWrite($contentType)
	{
		return canRead($contentType);
	}

	protected function getMediaType($contentType){
		return strtolower(preg_replace("/^(.*?)(?:;.+)?$/", "$1", $contentType));
	}

	protected function isCompatibleWith($contentType){
		$result = false;
		foreach($this->getAllowedContentTypes() as $type) {
			$result = $this->getMediaType($type) == $this->getMediaType($contentType);
		}
		return $result;
	}

	protected abstract function getAllowedContentTypes();
} 