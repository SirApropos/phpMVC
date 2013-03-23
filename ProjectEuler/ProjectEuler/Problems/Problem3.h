#pragma once
#include "../stdafx.h"
#include "Problem.h"

#ifndef Problem3Def
#define Problem3Def
using namespace Problems;
namespace Problems{
	class Problem3 :
		public Problem
	{
	private:
		__int64 target;
		List<int> primes;
	public:
		Problem3(void);
		int run(void);
	};
}
#endif