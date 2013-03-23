#include "Problem6.h"


Problem6::Problem6(void)
{
	setName("Problem 6");
	target = 100;
}


Problem6::~Problem6(void)
{
}

__int64 Problem6::run(void){
	int sum = 0;
	int square = 0;
	for(int i = 1; i<=target;i++){
		sum += i*i;
		square += i;
	}
	return (square*square)-sum;
}