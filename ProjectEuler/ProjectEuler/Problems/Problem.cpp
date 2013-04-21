#include "Problem.h"
#include <iostream>
using namespace Problems;

Problem::Problem(void){
	setName("Default Name");
};

std::string Problem::getName(){
	return *name;
}

void Problem::setName(std::string name){
	this->name = new std::string(name);
}
