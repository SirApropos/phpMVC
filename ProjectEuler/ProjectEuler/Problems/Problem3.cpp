#include "Problem3.h"

Problem3::Problem3(void)
{
	setName("Problem 3");
	target = 600851475143;
}

__int64 Problem3::run(void){
	return (EulerUtils::findFactors(target)).last();
}