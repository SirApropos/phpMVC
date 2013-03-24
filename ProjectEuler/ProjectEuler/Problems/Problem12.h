#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem12def
#define Problem12def
using namespace Problems;
namespace Problems{
class Problem12 :
	public Problem
{
public:
	Problem12(void);
	~Problem12(void);
	__int64 run(void);
private:
	int target;
};
}
#endif