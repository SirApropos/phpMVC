#include "Problem.h"
#include <iostream>
using namespace Problems;

Problem::Problem(void){};

std::string Problem::getName(){
	return name;
}

void Problem::setName(std::string name){
	this->name = _strdup(name.data());
}