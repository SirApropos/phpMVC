#include "Problem16.h"

Problem16::Problem16(void)
{
	setName("Problem 16");
	target = 1000;
}

__int64 Problem16::run(void){
	BigInt bi = BigInt(2).pow(target);
	List<int> digits = bi.toList();
	__int64 result = 0;
	digits.foreach([&](int digit){
		result += digit;
		return false;
	});
	return result;
}


Problem16::~Problem16(void)
{
}
