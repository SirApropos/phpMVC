#include "Problem9.h"


Problem9::Problem9(void)
{
	setName("Problem 9");
	target = 1000;
}


Problem9::~Problem9(void)
{
}

int Problem9::run(void){
	int result = 0;
	int a_max = target/3;
	int b_max = target/2;
	for(int a = 1;a<a_max && result == 0;a++){
		for(int b = a+1;b<b_max && result == 0;b++){
			int c = target - b - a;
			if((a * a + b * b) == c * c){
				result = a * b * c;
			}
		}
	}
	return result;
}