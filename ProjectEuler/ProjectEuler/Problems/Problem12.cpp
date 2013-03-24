#include "Problem12.h"


Problem12::Problem12(void)
{
	setName("Problem 12");
	target = 500;
}


Problem12::~Problem12(void)
{
}

__int64 Problem12::run(void){
	List<int> * primes = &List<int>();
	__int64 result = 0;
	__int64 triangle = 1;
	for(int i = 2;result == 0;i++){
		triangle +=i;
		List<int> * factors = &EulerUtils::findFactors(triangle,primes);
		Map<int, int> * factorMap = &Map<int, int>();
		factors->foreach([&](int factor){
			factorMap->put(factor, (factorMap->containsKey(factor) ? factorMap->get(factor) + 1 : 1));
			return false;
		});
		int numFactors = 1;
		factorMap->foreach([&](int key, int value){
			numFactors *= value+1;
			return false;
		});
		if(numFactors >= target){
			result = triangle;
		}
	}
	return result;
}