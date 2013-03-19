#pragma once
#include <string>
using namespace std;

#ifndef Problem
namespace Problems{
	class Problem
	{
	public:
		Problem(void);
		string getName(void);
		virtual int run() = 0;
	protected:
		void setName(string name);
	private:
		string name;
	};
}
#endif