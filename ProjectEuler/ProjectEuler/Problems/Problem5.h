#pragma once
#include "../stdafx.h"
#include "Problem.h"

#ifndef Problem5Def
#define Problem5Def
using namespace Problems;
namespace Problems{
	class Problem5 :
		public Problem
	{
	private:
		int target;
	public:
		Problem5(void);
		__int64 run(void);
	};
}
#endif