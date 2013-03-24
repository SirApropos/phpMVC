#pragma once
#include "List.h"
#include "Pair.h"
#ifndef MapDef
#define MapDef
template<class K, class V>
class Map
{
private:
	List<Pair<K,V> *> * pairs;
	int indexOf(K key){
		int index = -1;
		int i = 0;
		pairs->foreach([&](Pair<K,V> * pair){
			if(pair->getKey() == key){
				index = i;
			}
			i++;
			return index > -1;
		});
		return index;
	}

public:
	void put(K key, V value){
		int index = indexOf(key);
		if(index == -1){
			Pair<K,V> * pair = new Pair<K,V>(key, value);
			pairs->add(pair);
		}else{
			pairs->get(index)->setValue(value);
		}
	};
	V get(K key){
		int index = indexOf(key);
		return (index == -1) ? NULL : pairs->get(index)->getValue(); 
	}
	void remove(K key){
		int index = indexOf(key);
		if(index >= 0){
			pairs->remove(index);
		}
	}

	bool containsKey(K key){
		return indexOf(key) > -1;
	};

	List<K> keySet(){
		List<K> * keys = new List<K>();
		pairs->foreach([&](Pair<K,V> * pair){
			keys->add(pair->getKey());
		});
		return *keys;
	}

	template<typename Func>
	void foreach(Func& fn){
		pairs->foreach([&](Pair<K,V> * pair){
			return fn(pair->getKey(), pair->getValue());
		});
	}

	Map(){
		pairs = new List<Pair<K,V> *>();
	}
	~Map(void){

	}
};
#endif
