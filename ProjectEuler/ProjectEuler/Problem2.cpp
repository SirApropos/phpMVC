#include "Problem2.h"
#include <iostream>

Problem2::Problem2(void){
	setName("Problem 2");
}

int Problem2::run(void){
	int total = 0;
	int prev = 0;
	int cur = 1;
	while(cur <=4000000){
		if(cur % 2 == 0){
			total += cur;
		}
		int temp = prev;
		prev = cur;
		cur = prev + temp;
	}
	return total;
}