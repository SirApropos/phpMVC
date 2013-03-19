#pragma once
#include "problem.h"
#include "stdafx.h"

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