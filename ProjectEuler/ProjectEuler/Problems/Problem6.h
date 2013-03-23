#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem6Def
#define Problem6Def
using namespace Problems;
namespace Problems{
	class Problem6 :
		public Problem
	{
	public:
		Problem6(void);
		~Problem6(void);
		__int64 run(void);
	private:
		int target;
	};
}
#endif