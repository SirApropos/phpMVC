#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem8def
#define Problem8def
using namespace Problems;
namespace Problems{
class Problem8 :
	public Problem
{
public:
	Problem8(void);
	~Problem8(void);
	int run(void);
private:
	char * target;
};
}
#endif