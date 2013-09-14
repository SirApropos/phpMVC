#pragma once
#include "problem.h"
#include "../stdafx.h"
#ifndef Problem22Def
#define Problem22Def
using namespace Problems;
namespace Problems{
	class Problem22 :
		public Problem
	{
	private:
		__int64 getValue(std::string str);
		int getCharValue(char c);
	public:
		Problem22(void);
		virtual ~Problem22(void);
		__int64 run(void);
	};
}
#endif