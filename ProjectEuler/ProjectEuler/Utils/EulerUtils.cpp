#include "EulerUtils.h"
#include <cmath>
Problems::EulerUtils::EulerUtils(void)
{
}


int Problems::EulerUtils::modularPow(__int64 base, int exponent, int modulus){
	int result = 1;
	while(exponent > 0){
			if(exponent % 2 == 1){
				result = (result * base) % modulus;
			}
			exponent /= 2;
			base = (base*base) % modulus;
	}
	return (int)result;
}

bool Problems::EulerUtils::isPrime(__int64 num, int accuracy){
	bool result = false;
	if(num ==3 || num == 2){
		result = true;
	}else if(num % 2 ==0){
		result = false;
	}else{
		__int64 d = num-1;
		int s = 0;
		while(d % 2 == 0){
			d >>= 1;
			s++;
		}
		bool composite = false;
		for(int i=0;i<accuracy && !composite;i++){
			bool skip = false;
			__int64 r = (rand() % (num - 3)) + 2;
			int x = modularPow(r, d, num);
			if( x == 1 || x == num-1){
				continue;
			}
			for(int j=1;j<s;j++){
				x = (x*x) % num;
				if(x == 1){
					composite = true;
					break;
				}else if(x == num-1){
					skip = true;
					break;
				}
			}
			if(skip){
				continue;
			}
			composite = true;
		}
		result = (!composite);
	}
	return result;
}

int Problems::EulerUtils::findNextPrime(List<int> * &primes, __int64 target){
	if(primes->size() == 0){
		primes->add(2);
	}else{
 		for(int i=(primes->last() == 2) ? 3 : (primes->last() + 2);i<=target || target == -1;i+=2){
			if(isPrime(i)){
				primes->add(i);
				break;
			}
		}
	}
	return primes->last();
}

List<int> Problems::EulerUtils::findFactors(__int64 target, List<int> * primes){
	if(primes->size() == 0){
		findNextPrime(primes, target);
	}
	List<int> result = List<int>();
	__int64 remainder = target;
	if(primes->size() > 1){
		primes->foreach([&](int prime){
			while(remainder % prime == 0){
				remainder /= prime;
				result.add(prime);
			}
			return remainder == 0;
		});
	}
	if(remainder > 1){
		while(primes->last() < remainder){
			int prime = primes->last();
			if(remainder % prime == 0){
				remainder /= prime;
				result.add(prime);
			}else{
				findNextPrime(primes, target);
			}
		}
		result.add((int)remainder);
	}
	return result;
}