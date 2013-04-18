#pragma once
#include <iostream>
#include <typeinfo>
#include <functional>
#ifndef Taglibdef
#define Taglibdef
template <class T>
void println(T obj){
	std::cout << obj << std::endl; 
}

template<typename T>
void foreach(std::function<void(T obj)> fn){
	println (fn.result_type);
	getchar();
//	std::cout << std::type_info(fn(1)) << std::endl;
	for(int i=0;i<10;i++){
		fn(i);
	}
}
#endif