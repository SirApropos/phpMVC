#include "Timer.h"
Timer::Timer(void){
	unsigned __int64 freq;
	QueryPerformanceFrequency((LARGE_INTEGER*)&freq);
	timerFrequency = (1.0/freq);
}
void Timer::start(){
	QueryPerformanceCounter((LARGE_INTEGER *)&startTime);
}
void Timer::stop(){
	QueryPerformanceCounter((LARGE_INTEGER *)&endTime);
}
double Timer::getTime(){
	return ((endTime-startTime) * timerFrequency);
}