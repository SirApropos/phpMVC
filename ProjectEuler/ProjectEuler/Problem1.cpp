#include "Problem1.h"
#include <iostream>

Problem1::Problem1(){
	setName("Problem 1");
	std::cout << "Name set to " << getName() << std::endl;
	getchar();
	limit = 1000;
}


int Problem1::solution1(){
	int a = 0;
	for(int i=3;i<limit;i++){
		if(i % 3 == 0 || i % 5 == 0) a+=i;
	}
	return a;
}

int Problem1::solution2(){
	int a = 0;
	for(int j=15; j>=3; j-=2){
		for(int i=j; i<limit; i+=j){
			a+=i;
		}
		if(j == 15){
			a = -a;
			j = 7;
		}
	}
	return a;
}

int Problem1::run(void)
{
	return solution2();
}