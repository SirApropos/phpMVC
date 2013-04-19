#pragma once
#include "problem.h"
#include "../stdafx.h"
#ifndef Problem20Def
#define Problem20Def
using namespace Problems;
namespace Problems{
	class Problem20 :
		public Problem
	{
	private:
		int target;
	public:
		Problem20(void);
		~Problem20(void);
		__int64 run(void);
	};
}
#endif