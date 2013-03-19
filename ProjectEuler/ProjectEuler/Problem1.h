#pragma once
#ifndef Problem1
#include "Problem.h"
#include <iostream>

using namespace std;
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