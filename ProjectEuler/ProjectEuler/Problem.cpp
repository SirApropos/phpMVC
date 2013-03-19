#include "Problem.h"
#include <iostream>
using namespace Problems;

Problem::Problem(void){};

string Problem::getName(){
	return Problem::name;
}

void Problem::setName(string name){
	cout << "Setting name to: " << name << endl;;
	Problem::name = name;
}
