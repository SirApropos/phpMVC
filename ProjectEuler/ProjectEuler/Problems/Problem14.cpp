#include "Problem14.h"

Problem14::Problem14(void)
{
	setName("Problem 14");
	target = 1000000;
	bucketSize = target/1000;
	calculated = new Map<int, Map<__int64, int> *>();
}

int Problem14::findBucket(__int64 pos){
	return (int)(pos / bucketSize);
}
__int64 Problem14::run(void){
	__int64 result = 0;
	__int64 longest = 0;
	for(int i=1;i<target;i++){
		__int64 temp = runSequence(i);
		if(temp > longest){
			result = i;
			longest = temp;
		}
	}
	return result;
}
/*
	Inefficient, since there's no caching of sequence results.
	However, it runs much, much faster than the implementation using caching.
	Clearly, there is a problem with Map.
*/
__int64 Problem14::runSequence(__int64 pos){
	__int64 steps = 1;
	if(pos >1){
		steps += runSequence((pos % 2 == 0) ? pos/2 : 3*pos + 1);
	}
	return steps;
}


/*
	Incredibly slow. I suspect due to the implementation of Map.
*/
__int64 Problem14::runSequence2(__int64 pos){
	__int64 steps = 1;
	if(pos > 1){
		int bucket = findBucket(pos);
		if(!calculated->containsKey(bucket)){
 			calculated->put(bucket, new Map<__int64, int>());
		}
		Map<__int64, int> * map = calculated->get(bucket);
		if(map->containsKey(pos)){
			steps = map->get(pos);
		}else{
			steps += runSequence((pos % 2 == 0) ? pos/2 : 3*pos + 1);
			map->put(pos,steps);
		}
	}
	return steps;
}



Problem14::~Problem14(void)
{
}
