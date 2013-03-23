#include "Problems/Problem.h"
#include "Problems/Problem1.h"
#include "Problems/Problem2.h"
#include "Problems/Problem3.h"
#include "Problems/Problem4.h"
#include "Problems/Problem5.h"
#include "Problems/Problem6.h"
#include "Problems/Problem7.h"
#include "Problems/Problem8.h"
#include "Problems/Problem9.h"
#include "Utils/Timer.h"
#include "stdafx.h"

using namespace Problems;
void runProblem(Problem * &problem){
	Timer * timer = &Timer();
	timer->start();
	int sol = problem->run();
	timer->stop();
	std::cout << problem->getName() << ": " << sol << " in " << timer->getTime() << " seconds." << std::endl;
}

void runProblems(List<Problem *> * problems){
	problems->foreach([&](Problem * problem){
		runProblem(problem);
		return NULL;
	});
}

void init(){
	List<Problem *> problems;
	problems.add(&Problem1());
	problems.add(&Problem2());
	problems.add(&Problem3());
	problems.add(&Problem4());
	problems.add(&Problem5());
	problems.add(&Problem6());
	problems.add(&Problem7());
	problems.add(&Problem8());
	problems.add(&Problem9());
	runProblems(&problems);
}

int main(){
	init();
	getchar();
	return EXIT_SUCCESS;
}


