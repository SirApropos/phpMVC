#pragma once
#include <iostream>
#include <typeinfo>
#include <functional>
#ifndef Taglibdef
#define Taglibdef
template <class T>
void println(T obj){
	print(obj);
	std::cout << std::endl;
}
template <class T>
void print(T obj){
	std::cout << obj;
}
#endif