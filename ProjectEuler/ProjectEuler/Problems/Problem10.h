#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem10def
#define Problem10def
using namespace Problems;
namespace Problems{
class Problem10 :
	public Problem
{
public:
	Problem10(void);
	~Problem10(void);
	__int64 run(void);
private:
	int target;
};
}
#endif