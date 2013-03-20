#pragma once
#include "stdafx.h"
#include "Problem.h"

using namespace Problems;
namespace Problems{
	class Problem4 :
		public Problem
	{
	private:
		int max;
	public:
		Problem4(void);
		int run(void);
	};
}