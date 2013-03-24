#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem11def
#define Problem11def
using namespace Problems;
namespace Problems{
class Problem11 :
	public Problem
{
public:
	Problem11(void);
	~Problem11(void);
	__int64 run(void);
private:
	char * target;
	int size;
};
}
#endif