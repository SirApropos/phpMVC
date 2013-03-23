#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem7def
#define Problem7def
using namespace Problems;
namespace Problems{
class Problem7 :
	public Problem
{
public:
	Problem7(void);
	~Problem7(void);
	__int64 run(void);
private:
	int target;
};
}
#endif