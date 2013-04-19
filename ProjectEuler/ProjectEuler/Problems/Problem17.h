#pragma once
#include "problem.h"
#include "../stdafx.h"
#ifndef Problem17def
#define Problem17def
using namespace Problems;
namespace Problems{
	class Problem17 :
		public Problem
	{
	private:
		int target;
		int getNumChars(int num);
		int getNumCharsDigit(int digit);
	public:
		Problem17(void);
		~Problem17(void);
		__int64 run(void);
	};
}
#endif