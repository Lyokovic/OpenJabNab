# Makefile pour le compilateur de mtl.
COMMON_OBJS=properties.o dumpbc.o log.o

COMP_OBJS=main_compiler.o

SIMU_SRC=main_simu.c linux_simu.c linux_simuaudio.c linux_simunet.c vmem.c vloader.c vlog.c vinterp.c vaudio.c vnet.c
SIMU_OBJS=$(SIMU_SRC:.c=.o)

CXXFLAGS=-DVSIMU -g3 -m32
CFLAGS=-DVSIMU -g3  -m32 # -std=c99 

CC=gcc
CXX=g++

all : mtl_simu mtl_comp

.PHONY : vcomp/libvcomp.a

vcomp/libvcomp.a :
	$(MAKE) -C vcomp libvcomp.a

mtl_comp : vcomp/libvcomp.a $(COMMON_OBJS) $(COMP_OBJS)
	$(CXX) $(CXXFLAGS)  $^ -o $@ vcomp/libvcomp.a

mtl_simu : vcomp/libvcomp.a $(COMMON_OBJS) $(SIMU_OBJS)
	$(CXX) $(CXXFLAGS)  $^ -o $@ vcomp/libvcomp.a

clean :
	$(MAKE) -C vcomp clean
	rm -f $(COMMON_OBJS) $(COMP_OBJS) $(SIMU_OBJS)
