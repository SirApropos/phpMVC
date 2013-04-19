#pragma once
#include "List.h"
#include "BigInt.h"
#include "../stdafx.h"
#ifndef ProblemUtilsDef
#define ProblemUtilsDef
namespace Problems{
	class EulerUtils
	{
	private:
		static List<BigInt *> * factorialCache;
	public:
		static void initUtils(void);
		EulerUtils(void);
		static BigInt factorial(int target);
		static int findNextPrime(List<int> * &primes, __int64 target = -1);
		static List<int> findFactors(__int64 target, List<int> * primes = &List<int>());
		static bool isPrime(__int64 num, int accuracy=5);
		static int modularPow(__int64 base, __int64 exponent, __int64 modulus);
	};
}
#endif