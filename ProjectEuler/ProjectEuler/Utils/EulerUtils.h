#pragma once
#include "../stdafx.h"
#include <fstream>
#include <functional>
#ifndef ProblemUtilsDef
#define ProblemUtilsDef
namespace Problems{
	class EulerUtils
	{
	private:
		static List<BigInt *> * factorialCache;
	public:
		EulerUtils(void);
		static void initUtils(void);
		static BigInt& factorial(int target);
		static int findNextPrime(List<int> * &primes, __int64 target = -1);
		static List<int> findFactors(__int64 target, List<int> * primes = &List<int>());
		static bool isPrime(__int64 num, int accuracy=5);
		static int modularPow(__int64 base, __int64 exponent, __int64 modulus);
		static List<std::string *> readFile(std::string filename);
		static List<std::string *> readFile(std::string filename, List<std::string *>& list);
		static std::function<int(std::string, std::string)> getStringComparator(void);
		static std::function<int(std::string *, std::string *)> getStringPointerComparator(void);
	};
}
#endif