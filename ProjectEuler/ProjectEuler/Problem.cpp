#include "Problem.h"
using namespace Problems;

Problem::Problem(void){};

std::string Problem::getName(){
	return name;
}

void Problem::setName(std::string name){
	//char * chars = new char[name.size()+1];
	//strcpy_s(chars, name.size()+1, name.c_str());
	Problem::name = name;
}
