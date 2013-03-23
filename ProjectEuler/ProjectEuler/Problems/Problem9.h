#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem9def
#define Problem9def
using namespace Problems;
namespace Problems{
class Problem9 :
	public Problem
{
public:
	Problem9(void);
	~Problem9(void);
	__int64 run(void);
private:
	int target;
};
}
#endif