#include "Problem7.h"


Problem7::Problem7(void)
{
	setName("Problem 7");
	target = 10001;
}


Problem7::~Problem7(void)
{
}

__int64 Problem7::run(void){
	List<int> * primes = &List<int>();
	while(primes->size() < target){
		EulerUtils::findNextPrime(primes);
	}
	return primes->last();
}