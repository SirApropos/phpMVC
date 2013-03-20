#include "EulerUtils.h"

EulerUtils::EulerUtils(void)
{
}

int EulerUtils::findNextPrime(List<int> * &primes, __int64 target){
	if(primes->size() == 0){
		primes->add(2);
	}else{
 		for(int i=primes->last()+1;i<=target;i++){
			bool isPrime = true;
			primes->foreach([&](int prime){
				if((i % prime) == 0){
					isPrime = false;
				}
				return !isPrime;
			});
			if(isPrime){				
				primes->add(i);
				break;
			}
		}
	}
	return primes->last();
}

List<int> EulerUtils::findFactors(__int64 target, List<int> * primes){
	if(primes->size() == 0){
		findNextPrime(primes, target);
	}
	List<int> result = List<int>();
	__int64 remainder = target;
	while(primes->last() < remainder){
		int prime = primes->last();
		if(remainder % prime == 0){
			remainder /= prime;
			result.add(prime);
		}else{
			findNextPrime(primes, target);
		}
	}
	result.add(remainder);
	return result;
}