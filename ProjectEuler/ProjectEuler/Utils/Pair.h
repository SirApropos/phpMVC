#pragma once
#ifndef PairDef
#define PairDef
template<class K, class V>
class Pair{
private:
	K key;
	V value;
public:
	K getKey(){
		return key;
	};
	V getValue(){
		return value;
	};
	void setKey(K key){
		this->key = key;
	};
	void setValue(V value){
		this->value = value;
	};

	Pair(K key, V value){
		this->key = key;
		this->value = value;
	};
};
#endif