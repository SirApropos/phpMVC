#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem13def
#define Problem13def
using namespace Problems;
	namespace Problems{
	class Problem13 :
		public Problem
	{
	public:
		Problem13(void);
		~Problem13(void);
		__int64 run(void);
	private:
		char * target;
		int size;
		int numRows;
	};
}
#endif