#include "Problem20.h"


Problem20::Problem20(void)
{
	setName("Problem 20");
	target = 100;
}


Problem20::~Problem20(void)
{
}

__int64 Problem20::run(void){
	__int64 result = 0;
	BigInt bi = EulerUtils::factorial(target);
	List<int> digits = bi.toList();
	digits.foreach([&](int digit){
		result += digit;
		return false;
	});
	return result;
}
