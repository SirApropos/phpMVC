#pragma once
#include "stdafx.h"
#include "Problem.h"

using namespace Problems;

namespace Problems{
	class Problem5 :
		public Problem
	{
	private:
		int target;
	public:
		Problem5(void);
		int run(void);
	};
}