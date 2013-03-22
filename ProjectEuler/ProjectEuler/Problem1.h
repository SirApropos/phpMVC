#pragma once
#include "stdafx.h"
#include "problem.h"
#ifndef Problem1Def
#define Problem1Def
using namespace Problems;
namespace Problems{
	class Problem1 : public Problem{
	private:
		int limit;
		int solution1();
		int solution2();
	public:
		Problem1(void);
		int run(void);
	};

}
#endif