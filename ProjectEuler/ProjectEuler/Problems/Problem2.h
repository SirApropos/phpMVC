#pragma once
#include "../stdafx.h"
#include "Problem.h"
#ifndef Problem2Def
#define Problem2Def
using namespace Problems;
namespace Problems{
	class Problem2 : public Problem
	{
	public:
		Problem2(void);
		__int64 run(void);
	};
}
#endif