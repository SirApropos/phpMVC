#pragma once
#ifndef TimerDef
#define TimerDef
#include <windows.h>
class Timer{
	private:
		double timerFrequency;
		unsigned __int64 startTime;
		unsigned __int64 endTime;
	public:
		Timer(void);
		void start();
		void stop();
		double getTime();
};
#endif