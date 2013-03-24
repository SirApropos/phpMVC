#include "Problem10.h"


Problem10::Problem10(void)
{
	setName("Problem 10");
	target = 2000000;
}


Problem10::~Problem10(void)
{
}

__int64 Problem10::run(void){
	List<int> * primes = &List<int>();
	__int64 result = 0;
	while(primes->last() < target){
		if(primes->last() == EulerUtils::findNextPrime(primes, target)){
			break;
		}else{
			result += primes->last();
		}
	}
	return result;
}