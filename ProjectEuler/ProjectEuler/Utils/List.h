#pragma once
#include <functional>
#ifndef ListDef
#define ListDef
template<class T>
class List
{
private:
	T * contents;
	int index;
	int curSize;
	void scaleUp(){
		curSize = (int)(curSize * 1.5 + 1);
		copyToScale();
	}
	void scaleDown(){
		curSize = (int)((curSize - 1) * (2.0/3.0));
		if(curSize < 10) curSize = 10;
		copyToScale();
	}
	void copyToScale(){
		T * copy = new T[curSize];
		for(int i=0;i<=index;i++){
			copy[i] = contents[i];
		}
		contents = copy;
	}
public:
	List(void){
		contents = new T[10];
		index=0;
		curSize=10;
	};

	List(List<T>& other){
		contents = new T[other.curSize];
		index = other.index;
		curSize = other.curSize;
		for(int i=0;i<size();i++){
			contents[i] = other.contents[i];
		}
	}

	T first(){
		return index > 0 ? contents[0] : NULL;
	}
	T last(){
		return index > 0 ? contents[index-1] : NULL;
	}
	bool contains(T obj){
		return (indexOf(obj) >= 0);
	}

	int indexOf(T obj, int offset=0){
		int result = -1;
		for(int i=offset;i<index;i++){
			if(contents[i] == obj){
				result = i;
				break;
			}
		}
		return result;
	}

	void add(T obj){
		if(index == curSize){
			scaleUp();
		}
		contents[index++] = obj;
	}

	void removeObj(T obj){
		for(int i=0;i<index;i++){
			if(contents[i] == obj){
				remove(i);
				break;
			}
		}
	}

	void remove(int index){
		if(index < this->index){
			for(int i = index; i<this->index;i++){
				contents[i] = contents[i+1];
			}
			this->index--;
			contents[this->index] = NULL;
			if(curSize > 10 && (curSize - 1) * 0.5 < this->index){
				scaleDown();
			}
		}
	}
	T get(int index){
		return (index < this->index) ? contents[index] : NULL;
	}
	void set(int index, T obj){
		if(index >= size()){
			for(int i=size();i<index;i++){
				add(NULL);
			}
		}
		contents[index] = obj;
	}

	int size(){
		return index;
	}
	bool containsAll(List<T> * &compare){
		List<T> used;
		bool result = true;
		compare->foreach([&](T item){
			int offset=0;
			while(true){
				offset = indexOf(item,offset);
				if(offset == -1){
					result = false;
					return true;
				}else if(!(&used)->contains(offset)){
					(&used)->add(offset);
					break;
				}
				offset++;
			}
			return false;
		});
		return result;
	}

	void addAll(List<T> * &list){
		list->foreach([](T obj){
			add(obj);
			return false;
		});
	}

	void foreach(std::function<bool(T obj)> fn){
		for(int i=0;i<index;i++){
			if(fn(contents[i])){
				break;
			}
		}
	}
};
#endif