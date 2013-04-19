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
#endif