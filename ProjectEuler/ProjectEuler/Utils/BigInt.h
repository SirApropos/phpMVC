#pragma once
#include "../stdafx.h"
#include <sstream>
#include <iostream>
#ifndef BigIntDef
#define BigIntDef
class BigInt
{
private:
	List<__int64> * buckets;
	void add(BigInt& bi);
	void subtract(BigInt& bi);
	void multiply(BigInt& bi);
	void divide(BigInt& bi);
	void addToBucket(int bucket, __int64 value);
	void normalize(int bucket=0);
	void init(__int64=0);
	static const __int64 MAX_SIZE = 1000000000000000;
	bool negative;
public:
	std::string toString();
	BigInt(void);
	~BigInt(void);
	BigInt(float f);
	BigInt(long l);
	BigInt(int i);
	BigInt(__int64 i);
	BigInt(double i);
	BigInt(BigInt& other);
	int * toArray();
	List<int> BigInt::toList();
	BigInt& pow(int exponent);
	BigInt& operator++();
	BigInt& operator++(int);
	BigInt& operator--();
	BigInt& operator--(int);
	BigInt& operator+ (BigInt& param);
	BigInt& operator- (BigInt& param);
	BigInt& operator* (BigInt& param);
	BigInt& operator/ (BigInt& param);
	BigInt& operator+= (BigInt& param);
	BigInt& operator-= (BigInt& param);
	BigInt& operator*= (BigInt& param);
	BigInt& operator/= (BigInt& param);
	BigInt& operator+ (__int64 param);
	BigInt& operator- (__int64 param);
	BigInt& operator* (__int64 param);
	BigInt& operator/ (__int64 param);
	BigInt& operator+= (__int64 param);
	BigInt& operator-= (__int64 param);
	BigInt& operator*= (__int64 param);
	BigInt& operator/= (__int64 param);
	bool operator== (__int64 param);
	bool operator!= (__int64 param);
	bool operator< (__int64 param);
	bool operator> (__int64 param);
	bool operator== (BigInt& param);
	bool operator!= (BigInt& param);
	bool operator< (BigInt& param);
	bool operator> (BigInt& param);
	friend std::ostream& operator<< (std::ostream& os, BigInt& other);
};
#endif