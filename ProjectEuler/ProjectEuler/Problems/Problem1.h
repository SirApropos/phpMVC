#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem1Def
#define Problem1Def
using namespace Problems;
namespace Problems{
	class Problem1 : public Problem{
	private:
		int limit;
		__int64 solution1();
		__int64 solution2();
	public:
		Problem1(void);
		__int64 run(void);
	};

}
#endif