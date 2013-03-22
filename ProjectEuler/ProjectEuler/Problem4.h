#pragma once
#include "stdafx.h"
#include "Problem.h"

#ifndef Problem4Def
#define Problem4Def
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
#endif