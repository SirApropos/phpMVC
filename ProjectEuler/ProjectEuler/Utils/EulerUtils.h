#pragma once
#include "../stdafx.h"
#ifndef ProblemUtilsDef
#define ProblemUtilsDef
namespace Problems{
	class EulerUtils
	{
	public:
		EulerUtils(void);
		static int findNextPrime(List<int> * &primes, __int64 target = -1);
		static List<int> findFactors(__int64 target, List<int> * primes = &List<int>());
	};
}
#endif