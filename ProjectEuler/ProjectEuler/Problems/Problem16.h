#pragma once
#include "../stdafx.h"
#include "problem.h"
#include <sstream>
#ifndef Problem16def
#define Problem16def
using namespace Problems;
namespace Problems{
	class Problem16 :
		public Problem
	{
	public:
		Problem16(void);
		~Problem16(void);
		__int64 run(void);
	private:
		int target;
	};
}
#endif